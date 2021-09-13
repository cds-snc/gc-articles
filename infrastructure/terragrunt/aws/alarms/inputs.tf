variable "alb_arn" {
  description = "ALB ARN to monitor"
  type        = string
}

variable "alb_target_group_arn" {
  description = "ALB target group ARN to monitor"
  type        = string
}

variable "alb_target_response_time_average_maximum" {
  description = "Maximum average response time for the ALB target group in a 5 minute period"
  type        = number
}

variable "alb_target_5xx_maximum" {
  description = "Maximum number of 5xx responses from the ALB target group in a 5 minute period"
  type        = number
}

variable "alb_target_4xx_maximum" {
  description = "Maximum number of 4xx responses from the ALB target group in a 5 minute period"
  type        = number
}

variable "alb_5xx_maximum" {
  description = "Maximum number of 5xx responses from the ALB in a 5 minute period"
  type        = number
}

variable "canary_healthcheck_url_eng" {
  description = "URL for the English synthetic canary healthcheck."
  type        = string
}

variable "canary_healthcheck_url_fra" {
  description = "URL for the English synthetic canary healthcheck."
  type        = string
}

variable "cloudfront_arn" {
  description = "CloudFront ARN, used for DDoS alarm."
  type        = string
}

variable "cloudfront_distribution_id" {
  description = "CloudFront distribution ID to monitor"
  type        = string
}

variable "cloudfront_4xx_maximum" {
  description = "Allowed threshold of 4xx responses from CloudFront"
  type        = number
}

variable "cloudfront_5xx_maximum" {
  description = "Allowed threshold of 5xx responses from CloudFront"
  type        = number
}

variable "cloudfront_waf_web_acl_name" {
  description = "CloudFront WAF WEB ACL name to monitor."
  type        = string
}

variable "ecs_cpu_maximum" {
  description = "Maximum threshold of CPU use by the WordPress ECS service"
  type        = number
}

variable "ecs_memory_maximum" {
  description = "number threshold of memory use by the WordPress ECS service"
  type        = string
}

variable "ecs_cluster_name" {
  description = "WordPress ECS cluster name, used for CPU/memory utilization alarms"
  type        = string
}

variable "ecs_service_name" {
  description = "WordPress ECS service name, used for CPU/memory utilization alarms"
  type        = string
}

variable "efs_id" {
  description = "Elastic File System ID to monitor"
  type        = string
}

# Reference: https://github.com/cloudposse/terraform-aws-efs-cloudwatch-sns-alarms
variable "efs_burst_credit_balance" {
  description = "Recommend 192GB in Bytes (last hour where EFS can burst at 100 MB/sec)."
  type        = string
}

variable "efs_percent_io_limit" {
  description = "I/O percent limit of EFS.  Above this and performance drops."
  type        = string
}

variable "hosted_zone_id" {
  description = "Route53 hosted zone ID, used for DDoS alarm."
  type        = string
}

variable "rds_cluster_id" {
  description = "RDS cluster ID to monitor"
  type        = string
}

variable "rds_aurora_replica_lag_maximum" {
  description = "RDS cluster replica lag (milliseconds) between writer and reader instances"
  type        = number
}

variable "rds_cpu_maxiumum" {
  description = "RDS cluster maximum CPU utilization percentage"
  type        = number
}

variable "rds_freeable_memory_minimum" {
  description = "RDS cluster instance minimum threshold of freeable memory (Megabytes)"
  type        = number
}

variable "rds_swap_usage_maximum" {
  description = "RDS cluster instance maximum threshold of swap usage (Bytes)"
  type        = number
}

variable "slack_webhook_url" {
  description = "Incoming Slack webhook used to post alarm state changes"
  type        = string
  sensitive   = true
}

variable "sns_lambda_private_subnet_ids" {
  description = "Private subnet IDs to attach the SNS Lamba Slack notify function to"
  type        = list(string)
}

variable "sns_lambda_security_group_id" {
  description = "Security group ID for the SNS Lambda Slack notify function"
  type        = string
}

variable "wordpress_failed_login_maximum" {
  description = "Maximum number of failed WordPress login attempts in a 1 minute period"
  type        = string
}

variable "wordpress_log_group_name" {
  description = "WordPress CloudWatch log group name"
  type        = string
}
