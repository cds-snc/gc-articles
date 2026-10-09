resource "aws_cloudwatch_log_group" "wordpress_ecs_logs" {
  name              = "/aws/ecs/${var.cluster_name}"
  retention_in_days = 14
  tags              = var.core_tags
}

resource "aws_cloudwatch_log_group" "ecs_events" {
  name              = "/aws/lambda/${aws_lambda_function.ecs_events.function_name}"
  retention_in_days = 14
  tags              = var.core_tags
}

module "sentinel_forwarder" {
  source            = "github.com/cds-snc/terraform-modules//sentinel_forwarder?ref=v13.1.0"
  function_name     = "sentinel-forwarder"
  billing_tag_value = var.billing_tag_value

  # 273 or later: the first version that reads SENTINEL_HUB_ROLE_ARN
  # (aws-sentinel-connector-layer#306). 270 is the floor for python3.13.
  layer_arn = "arn:aws:lambda:ca-central-1:283582579564:layer:aws-sentinel-connector-layer:273"

  # Kept so rollback is a config change: the layer ignores these once
  # dce_endpoint and dcr_config are set. They are removed with the v1 path.
  customer_id = var.sentinel_customer_id
  shared_key  = var.sentinel_shared_key

  # v2 (Logs Ingestion API). No stored secret: the Lambda's role assumes the
  # Sentinel forwarder hub role in Log Archive (cds-snc/cds-aws-lz), mints a
  # token there with IAM outbound identity federation, and Entra accepts it for
  # the managed identity sentinel-forwarder-v2-aws-hub. Nothing is set up in
  # this account. These name Azure resources in cds-snc/sentinel and
  # cds-snc/cds-azure-resources.
  dce_endpoint = "https://dce-sentinel-forwarder-v2-153n.canadacentral-1.ingest.monitor.azure.com"
  dcr_config = {
    AWSCloudWatchLog = {
      dcrImmutableId = "dcr-6eccfc9e7ef34cd293566d5073d551f6"
      streamName     = "Custom-AWSCloudWatchLog_v2_Input"
    }
  }
  azure_client_id = "97057b1c-9b09-4dd3-a4f9-d9df6d181949"
  azure_tenant_id = "221ca1d3-b3f2-4346-8abc-88f802495c7d"
  hub_role_arn    = "arn:aws:iam::274536870005:role/sentinel-forwarder-hub"

  cloudwatch_log_arns = [aws_cloudwatch_log_group.wordpress_ecs_logs.arn]
}

resource "aws_cloudwatch_log_subscription_filter" "sentinel_forwarder" {
  name            = "All ECS logs"
  log_group_name  = aws_cloudwatch_log_group.wordpress_ecs_logs.name
  filter_pattern  = "[w1=\"*\"]"
  destination_arn = module.sentinel_forwarder.lambda_arn
  distribution    = "Random"
}