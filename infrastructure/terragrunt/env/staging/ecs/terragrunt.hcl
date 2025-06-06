include {
  path = find_in_parent_folders("root.hcl")
}

locals {
  environments = yamldecode(file(find_in_parent_folders("environments.yml")))
}

dependencies {
  paths = ["../network", "../ecr", "../load-balancer", "../database"]
}

dependency "network" {
  config_path = "../network"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    ecs_events_lambda_security_group_id = ""
    ecs_service_security_group_id = ""
    efs_security_group_id         = ""
    private_subnet_ids            = [""]
  }
}

dependency "ecr" {
  config_path = "../ecr"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    wordpress_repository_arn = ""
    wordpress_repository_url = ""
    apache_repository_arn = ""
    apache_repository_url = ""
  }
}

dependency "load-balancer" {
  config_path = "../load-balancer"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    alb_target_group_arn = ""
    domain_name          = ""
    c3_distribution_id   = ""
  }
}

dependency "database" {
  config_path = "../database"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    database_host_secret_arn         = ""
    database_name_secret_arn         = ""
    database_username_secret_arn     = ""
    database_password_secret_arn     = ""
    database_proxy_security_group_id = ""
  }
}

inputs = {
  alb_target_group_arn = dependency.load-balancer.outputs.alb_target_group_arn
  domain_name          = dependency.load-balancer.outputs.domain_name

  ecs_events_lambda_security_group_id = dependency.network.outputs.ecs_events_lambda_security_group_id
  ecs_service_security_group_id = dependency.network.outputs.ecs_service_security_group_id
  efs_security_group_id         = dependency.network.outputs.efs_security_group_id
  private_subnet_ids            = dependency.network.outputs.private_subnet_ids

  wordpress_repository_arn = dependency.ecr.outputs.wordpress_repository_arn
  wordpress_image          = dependency.ecr.outputs.wordpress_repository_url
  wordpress_image_tag      = "v${local.environments.staging.wordpress}"

  apache_repository_arn = dependency.ecr.outputs.apache_repository_arn
  apache_image          = dependency.ecr.outputs.apache_repository_url
  apache_image_tag      = "v${local.environments.staging.apache}"

  database_host_secret_arn         = dependency.database.outputs.database_host_secret_arn
  database_name_secret_arn         = dependency.database.outputs.database_name_secret_arn
  database_username_secret_arn     = dependency.database.outputs.database_username_secret_arn
  database_password_secret_arn     = dependency.database.outputs.database_password_secret_arn
  database_proxy_security_group_id = dependency.database.outputs.database_proxy_security_group_id

  c3_distribution_id = dependency.load-balancer.outputs.cloudfront_distribution_id

  cluster_name           = "wordpress"
  cpu_units              = "1024"
  memory                 = "3072"
  min_capacity           = 1
  max_capacity           = 2
  scale_in_cooldown      = 60
  scale_out_cooldown     = 60
  scale_threshold_cpu    = 20
  scale_threshold_memory = 40
}

terraform {
  source = "../../../aws//ecs"
}
