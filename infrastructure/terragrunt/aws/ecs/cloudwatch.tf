resource "aws_cloudwatch_log_group" "wordpress_ecs_logs" {
  # checkov:skip=CKV_AWS_158:Encryption using default CloudWatch service key is acceptable
  name              = "/aws/ecs/${var.cluster_name}"
  retention_in_days = 14
}
