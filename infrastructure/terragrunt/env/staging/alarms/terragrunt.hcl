include {
  path = find_in_parent_folders()
}

dependencies {
  paths = ["../hosted-zone", "../load-balancer", "../database", "../ecs"]
}

dependency "hosted-zone" {
  config_path = "../hosted-zone"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    zone_id = ""
  }
}

dependency "load-balancer" {
  config_path = "../load-balancer"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    alb_arn                     = ""
    alb_arn_suffix              = ""
    alb_target_group_arn_suffix = ""
    cloudfront_arn              = ""
    cloudfront_distribution_id  = ""
    cloudfront_waf_web_acl_name = ""
  }
}

dependency "database" {
  config_path = "../database"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    rds_cluster_id = ""
  }
}

dependency "ecs" {
  config_path = "../ecs"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    ecs_cloudfront_log_group_name = ""
    ecs_cluster_name              = ""
    ecs_event_log_group_name      = ""
    ecs_service_name              = ""
    efs_id                        = ""
  }
}

inputs = {
  alb_arn         = dependency.load-balancer.outputs.alb_arn
  alb_arn_suffix  = dependency.load-balancer.outputs.alb_arn_suffix

  alb_target_group_arn_suffix              = dependency.load-balancer.outputs.alb_target_group_arn_suffix
  alb_target_response_time_average_maximum = 2
  alb_target_4xx_maximum                   = 100

  healthcheck_domain = "articles.cdssandbox.xyz"
  healthcheck_path   = "/sign-in-se-connecter/"

  cloudfront_arn              = dependency.load-balancer.outputs.cloudfront_arn
  cloudfront_distribution_id  = dependency.load-balancer.outputs.cloudfront_distribution_id
  cloudfront_waf_web_acl_name = dependency.load-balancer.outputs.cloudfront_waf_web_acl_name
  cloudfront_4xx_maximum      = 100

  ecs_cluster_name         = dependency.ecs.outputs.ecs_cluster_name
  ecs_event_log_group_name = dependency.ecs.outputs.ecs_event_log_group_name
  ecs_service_name         = dependency.ecs.outputs.ecs_service_name
  ecs_cpu_maximum          = 50
  ecs_memory_maximum       = 50

  efs_id                   = dependency.ecs.outputs.efs_id
  efs_burst_credit_balance = "192000000000"
  efs_percent_io_limit     = "95"

  hosted_zone_id = dependency.hosted-zone.outputs.zone_id

  rds_cluster_id                 = dependency.database.outputs.rds_cluster_id
  rds_aurora_replica_lag_maximum = 2000
  rds_cpu_maxiumum               = 80
  rds_freeable_memory_minimum    = 64000000

  wordpress_failed_login_maximum = "5"
  wordpress_log_group_name       = dependency.ecs.outputs.ecs_cloudfront_log_group_name
}

terraform {
  source = "../../../aws//alarms"
}
