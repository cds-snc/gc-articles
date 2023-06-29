resource "aws_cloudwatch_log_group" "wordpress_ecs_logs" {
  name              = "/aws/ecs/${var.cluster_name}"
  retention_in_days = 14
}

resource "aws_cloudwatch_log_group" "ecs_events" {
  name              = "/aws/lambda/${aws_lambda_function.ecs_events.function_name}"
  retention_in_days = 14
}

module "sentinel_forwarder" {
  source            = "github.com/cds-snc/terraform-modules?ref=v6.1.0//sentinel_forwarder"
  function_name     = "sentinel-forwarder"
  billing_tag_value = var.billing_tag_value

  layer_arn = "arn:aws:lambda:ca-central-1:283582579564:layer:aws-sentinel-connector-layer:71"

  customer_id = var.sentinel_customer_id
  shared_key  = var.sentinel_shared_key

  cloudwatch_log_arns = [aws_cloudwatch_log_group.wordpress_ecs_logs.arn]
}
