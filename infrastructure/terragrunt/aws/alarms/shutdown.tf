module "schedule_shutdown" {
  count = var.env == "staging" ? 1 : 0

  source = "github.com/cds-snc/terraform-modules//schedule_shutdown?ref=v10.11.0"

  cloudwatch_alarm_arns = [
    aws_cloudwatch_metric_alarm.wordpress_errors.arn,
    aws_cloudwatch_metric_alarm.wordpress_warnings.arn,
    aws_cloudwatch_metric_alarm.alb_target_unhealthy_host.arn,
    aws_cloudwatch_metric_alarm.alb_target_response_time_average.arn,
  ]
  ecs_service_arns = [
    "arn:aws:ecs:${var.region}:${var.account_id}:service/${var.ecs_cluster_name}/${var.ecs_service_name}",
  ]
  rds_cluster_arns = [
    "arn:aws:rds:${var.region}:${var.account_id}:cluster:${var.rds_cluster_id}",
  ]

  schedule_shutdown = "cron(0 23 * * ? *)"       # 11pm UTC, every day
  schedule_startup  = "cron(0 11 ? * MON-FRI *)" # 11am UTC, Monday-Friday

  billing_tag_value = var.billing_tag_value
}
