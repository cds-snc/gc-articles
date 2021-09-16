resource "aws_iam_role" "wordpress_ecs_task" {
  name               = "${var.cluster_name}-ecs-task"
  assume_role_policy = data.aws_iam_policy_document.ecs_task_assume.json
  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}
resource "aws_iam_policy" "wordpress_ecs_task_get_secret_value" {
  name   = "WordpressEcsTaskGetSecretValue"
  path   = "/"
  policy = data.aws_iam_policy_document.wordpress_ecs_task_get_secret_value.json
}

resource "aws_iam_policy" "wordpress_ecs_task_get_ecr_image" {
  name   = "WordpressEcsTaskGetEcrImage"
  path   = "/"
  policy = data.aws_iam_policy_document.wordpress_ecs_task_get_ecr_image.json
}

resource "aws_iam_policy" "wordpress_ecs_task_efs" {
  count = var.enable_efs ? 1 : 0

  name   = "WordpressEcsTaskEfs"
  path   = "/"
  policy = data.aws_iam_policy_document.wordpress_ecs_task_efs[0].json
}

resource "aws_iam_role_policy_attachment" "wordpress_ecs_task_policy_attach" {
  role       = aws_iam_role.wordpress_ecs_task.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy"
}

resource "aws_iam_role_policy_attachment" "wordpress_ecs_task_ec2_policy_attach" {
  role       = aws_iam_role.wordpress_ecs_task.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonEC2ContainerServiceforEC2Role"
}

resource "aws_iam_role_policy_attachment" "wordpress_ecs_task_get_secret_value_policy_attach" {
  role       = aws_iam_role.wordpress_ecs_task.name
  policy_arn = aws_iam_policy.wordpress_ecs_task_get_secret_value.arn
}

resource "aws_iam_role_policy_attachment" "wordpress_ecs_task_get_ecr_image_policy_attach" {
  role       = aws_iam_role.wordpress_ecs_task.name
  policy_arn = aws_iam_policy.wordpress_ecs_task_get_ecr_image.arn
}

resource "aws_iam_role_policy_attachment" "wordpress_ecs_task_efs_policy_attach" {
  count = var.enable_efs ? 1 : 0

  role       = aws_iam_role.wordpress_ecs_task.name
  policy_arn = aws_iam_policy.wordpress_ecs_task_efs[0].arn
}

data "aws_iam_policy_document" "ecs_task_assume" {
  statement {
    actions = ["sts:AssumeRole"]
    principals {
      type        = "Service"
      identifiers = ["ec2.amazonaws.com"]
    }
    principals {
      type        = "Service"
      identifiers = ["ecs-tasks.amazonaws.com"]
    }
  }
}

data "aws_iam_policy_document" "wordpress_ecs_task_get_secret_value" {
  statement {
    effect = "Allow"
    actions = [
      "secretsmanager:GetSecretValue",
    ]
    resources = [
      var.database_host_secret_arn,
      var.database_name_secret_arn,
      var.database_username_secret_arn,
      var.database_password_secret_arn,
      aws_secretsmanager_secret_version.list_manager_api_key.arn,
      aws_secretsmanager_secret_version.list_manager_endpoint.arn,
      aws_secretsmanager_secret_version.list_manager_service_id.arn,
      aws_secretsmanager_secret_version.list_manager_notify_services.arn,
      aws_secretsmanager_secret_version.notify_api_key.arn,
      aws_secretsmanager_secret_version.wordpress_auth_key.arn,
      aws_secretsmanager_secret_version.wordpress_secure_auth_key.arn,
      aws_secretsmanager_secret_version.wordpress_logged_in_key.arn,
      aws_secretsmanager_secret_version.wordpress_nonce_key.arn,
      aws_secretsmanager_secret_version.wordpress_auth_salt.arn,
      aws_secretsmanager_secret_version.wordpress_secure_auth_salt.arn,
      aws_secretsmanager_secret_version.wordpress_logged_in_salt.arn,
      aws_secretsmanager_secret_version.wordpress_nonce_salt.arn
    ]
  }
}

data "aws_iam_policy_document" "wordpress_ecs_task_get_ecr_image" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:GetDownloadUrlForlayer",
      "ecr:BatchGetImage"
    ]
    resources = [
      var.wordpress_repository_arn
    ]
  }
}

data "aws_iam_policy_document" "wordpress_ecs_task_efs" {
  count = var.enable_efs ? 1 : 0

  statement {
    effect = "Allow"
    actions = [
      "elasticfilesystem:ClientWrite",
      "elasticfilesystem:ClientMount",
      "elasticfilesystem:DescribeMountTargets",
    ]
    resources = [
      aws_efs_file_system.wordpress[0].arn
    ]
  }
}
