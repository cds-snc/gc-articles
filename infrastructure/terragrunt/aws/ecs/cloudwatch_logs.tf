resource "aws_cloudwatch_log_group" "wordpress_ecs_logs" {
  name              = "/aws/ecs/${var.cluster_name}"
  retention_in_days = 14
}

resource "aws_cloudwatch_log_group" "ecs_events" {
  name              = "/aws/lambda/${aws_lambda_function.ecs_events.function_name}"
  retention_in_days = 14
}
