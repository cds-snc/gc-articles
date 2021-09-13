resource "aws_cloudwatch_log_group" "wordpress_ecs_logs" {
  name              = "/aws/ecs/${var.cluster_name}"
  retention_in_days = 14
}
