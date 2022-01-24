#
# ECS Fargate cluster
#
resource "aws_ecs_cluster" "wordpress" {
  name = var.cluster_name

  setting {
    name  = "containerInsights"
    value = "enabled"
  }

  capacity_providers = ["FARGATE"]

  default_capacity_provider_strategy {
    capacity_provider = "FARGATE"
    weight            = 1
    base              = 1
  }

  lifecycle {
    ignore_changes = [setting]
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

#
# Task
#

locals {
  wordpress_extra_config = <<-EOF
    define('WP_HOME','https://${var.domain_name}');
    define('WP_SITEURL','https://${var.domain_name}');
    define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
    EOF
}

data "template_file" "wordpress_container_definition" {
  template = file("container-definitions/wordpress.json.tmpl")

  vars = {
    ENABLE_EFS = var.enable_efs

    LIST_MANAGER_ENDPOINT        = aws_secretsmanager_secret_version.list_manager_endpoint.arn
    DEFAULT_LIST_MANAGER_API_KEY = aws_secretsmanager_secret_version.default_list_manager_api_key.arn
    DEFAULT_NOTIFY_API_KEY       = aws_secretsmanager_secret_version.default_notify_api_key.arn
    ENCRYPTION_KEY               = aws_secretsmanager_secret_version.encryption_key.arn
    S3_UPLOADS_BUCKET            = aws_secretsmanager_secret_version.s3_uploads_bucket.arn
    S3_UPLOADS_KEY               = aws_secretsmanager_secret_version.s3_uploads_key.arn
    S3_UPLOADS_SECRET            = aws_secretsmanager_secret_version.s3_uploads_secret.arn
    S3_UPLOADS_BUCKET_URL        = "https://${var.domain_name}"
    C3_AWS_ACCESS_KEY_ID         = aws_secretsmanager_secret_version.c3_aws_access_key_id.arn
    C3_AWS_SECRET_ACCESS_KEY     = aws_secretsmanager_secret_version.c3_aws_secret_access_key.arn
    C3_DISTRIBUTION_ID           = var.c3_distribution_id
    DEFAULT_DOMAIN               = var.domain_name
    WORDPRESS_IMAGE              = "${var.wordpress_image}:${var.wordpress_image_tag}"
    APACHE_IMAGE                 = "${var.apache_image}:${var.apache_image_tag}"
    WORDPRESS_DB_HOST            = var.database_host_secret_arn
    WORDPRESS_DB_NAME            = var.database_name_secret_arn
    WORDPRESS_DB_USER            = var.database_username_secret_arn
    WORDPRESS_DB_PASSWORD        = var.database_password_secret_arn
    WORDPRESS_CONFIG_EXTRA       = replace(chomp(local.wordpress_extra_config), "\n", "")
    WORDPRESS_AUTH_KEY           = aws_secretsmanager_secret_version.wordpress_auth_key.arn
    WORDPRESS_SECURE_AUTH_KEY    = aws_secretsmanager_secret_version.wordpress_secure_auth_key.arn
    WORDPRESS_LOGGED_IN_KEY      = aws_secretsmanager_secret_version.wordpress_logged_in_key.arn
    WORDPRESS_NONCE_KEY          = aws_secretsmanager_secret_version.wordpress_nonce_key.arn
    WORDPRESS_AUTH_SALT          = aws_secretsmanager_secret_version.wordpress_auth_salt.arn
    WORDPRESS_SECURE_AUTH_SALT   = aws_secretsmanager_secret_version.wordpress_secure_auth_salt.arn
    WORDPRESS_LOGGED_IN_SALT     = aws_secretsmanager_secret_version.wordpress_logged_in_salt.arn
    WORDPRESS_NONCE_SALT         = aws_secretsmanager_secret_version.wordpress_nonce_salt.arn
    JWT_AUTH_SECRET_KEY          = aws_secretsmanager_secret_version.jwt_auth_secret_key.arn
    WPML_SITE_KEY                = aws_secretsmanager_secret_version.wpml_site_key.arn

    AWS_LOGS_GROUP         = aws_cloudwatch_log_group.wordpress_ecs_logs.name
    AWS_LOGS_REGION        = var.region
    AWS_LOGS_STREAM_PREFIX = "${var.cluster_name}-task"
  }
}

resource "aws_ecs_task_definition" "wordpress_task" {
  family       = var.cluster_name
  cpu          = var.cpu_units
  memory       = var.memory
  network_mode = "awsvpc"

  requires_compatibilities = ["FARGATE"]
  execution_role_arn       = aws_iam_role.wordpress_ecs_task.arn
  task_role_arn            = aws_iam_role.wordpress_ecs_task.arn
  container_definitions    = data.template_file.wordpress_container_definition.rendered

  dynamic "volume" {
    for_each = var.enable_efs ? [1] : []
    content {
      name = "wp-content"

      efs_volume_configuration {
        file_system_id     = aws_efs_file_system.wordpress[0].id
        root_directory     = "/"
        transit_encryption = "ENABLED"

        authorization_config {
          access_point_id = aws_efs_access_point.wordpress[0].id
          iam             = "DISABLED"
        }
      }
    }
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

#
# Service
#
resource "aws_ecs_service" "wordpress_service" {
  name             = var.cluster_name
  cluster          = aws_ecs_cluster.wordpress.id
  task_definition  = aws_ecs_task_definition.wordpress_task.arn
  launch_type      = "FARGATE"
  platform_version = "1.4.0"
  propagate_tags   = "SERVICE"

  desired_count                      = var.min_capacity
  deployment_minimum_healthy_percent = 50
  deployment_maximum_percent         = 200
  health_check_grace_period_seconds  = 60

  deployment_controller {
    type = "ECS"
  }

  network_configuration {
    assign_public_ip = false
    subnets          = var.private_subnet_ids
    security_groups = [
      var.database_proxy_security_group_id,
      var.ecs_service_security_group_id
    ]
  }

  load_balancer {
    target_group_arn = var.alb_target_group_arn
    container_name   = "apache"
    container_port   = 443
  }

  lifecycle {
    ignore_changes = [
      desired_count, # updated by autoscaling
    ]
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

#
# ECS service scaling
#
resource "aws_appautoscaling_target" "wordpress" {
  service_namespace  = "ecs"
  resource_id        = "service/${aws_ecs_cluster.wordpress.name}/${aws_ecs_service.wordpress_service.name}"
  scalable_dimension = "ecs:service:DesiredCount"
  min_capacity       = var.min_capacity
  max_capacity       = var.max_capacity
}

resource "aws_appautoscaling_policy" "wordpress_cpu" {
  name               = "wordpress_cpu"
  policy_type        = "TargetTrackingScaling"
  service_namespace  = aws_appautoscaling_target.wordpress.service_namespace
  resource_id        = aws_appautoscaling_target.wordpress.resource_id
  scalable_dimension = aws_appautoscaling_target.wordpress.scalable_dimension

  target_tracking_scaling_policy_configuration {
    scale_in_cooldown  = var.scale_in_cooldown
    scale_out_cooldown = var.scale_out_cooldown
    predefined_metric_specification {
      predefined_metric_type = "ECSServiceAverageCPUUtilization"
    }
    target_value = var.scale_threshold_cpu
  }
}

resource "aws_appautoscaling_policy" "wordpress_memory" {
  name               = "wordpress_memory"
  policy_type        = "TargetTrackingScaling"
  service_namespace  = aws_appautoscaling_target.wordpress.service_namespace
  resource_id        = aws_appautoscaling_target.wordpress.resource_id
  scalable_dimension = aws_appautoscaling_target.wordpress.scalable_dimension

  target_tracking_scaling_policy_configuration {
    scale_in_cooldown  = var.scale_in_cooldown
    scale_out_cooldown = var.scale_out_cooldown
    predefined_metric_specification {
      predefined_metric_type = "ECSServiceAverageMemoryUtilization"
    }
    target_value = var.scale_threshold_memory
  }
}
