variable "alb_arn" {
  description = "ALB ARN to monitor"
  type        = string
}

variable "alb_arn_suffix" {
  description = "ALB ARN suffix to monitor"
  type        = string
}

variable "alb_target_group_arn_suffix" {
  description = "ALB target group ARN suffix to monitor"
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

variable "cloudfront_waf_web_acl_name" {
  description = "CloudFront WAF WEB ACL name to monitor."
  type        = string
}

variable "ecs_cpu_maximum" {
  description = "Maximum threshold of CPU use by the WordPress ECS service"
  type        = number
}

variable "ecs_memory_maximum" {
  description = "Maximum threshold of memory use by the WordPress ECS service"
  type        = string
}

variable "ecs_event_log_group_name" {
  description = "Name of the CloudWatch log group the ECS events are written to"
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

variable "slack_webhook_url" {
  description = "Incoming Slack webhook used to post alarm state changes"
  type        = string
  sensitive   = true
}

variable "wordpress_failed_login_maximum" {
  description = "Maximum number of failed WordPress login attempts in a 1 minute period"
  type        = string
}

variable "wordpress_log_group_name" {
  description = "WordPress CloudWatch log group name"
  type        = string
}
