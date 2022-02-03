#
# Kinesis Firehose
#
locals {
  cbs_satellite_bucket_arn    = "arn:aws:s3:::${var.cbs_satellite_bucket_name}"
  cbs_satellite_bucket_prefix = "waf_acl_logs/AWSLogs/${var.account_id}/"
}

resource "aws_kinesis_firehose_delivery_stream" "firehose_waf_logs" {
  name        = "aws-waf-logs-platform-mvp"
  destination = "extended_s3"

  server_side_encryption {
    enabled = true
  }

  extended_s3_configuration {
    role_arn           = aws_iam_role.firehose_waf_logs.arn
    prefix             = local.cbs_satellite_bucket_prefix
    bucket_arn         = local.cbs_satellite_bucket_arn
    compression_format = "GZIP"
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
    Terraform             = true
  }
}

resource "aws_kinesis_firehose_delivery_stream" "firehose_waf_logs_us_east" {
  provider = aws.us-east-1

  name        = "aws-waf-logs-platform-mvp-us-east"
  destination = "extended_s3"

  server_side_encryption {
    enabled = true
  }

  extended_s3_configuration {
    role_arn           = aws_iam_role.firehose_waf_logs.arn
    prefix             = local.cbs_satellite_bucket_prefix
    bucket_arn         = local.cbs_satellite_bucket_arn
    compression_format = "GZIP"
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
    Terraform             = true
  }
}

#
# S3 waf log destination
#
module "firehose_waf_log_bucket" {
  source            = "github.com/cds-snc/terraform-modules?ref=v0.0.33//S3"
  bucket_name       = "platform-ircc-${var.env}-waf-logs"
  billing_tag_value = var.billing_tag_value

  lifecycle_rule = [
    {
      id      = "expire"
      enabled = true
      expiration = {
        days = 30
      }
    }
  ]
}

#
# IAM
#
resource "aws_iam_role" "firehose_waf_logs" {
  name               = "FirehoseWafLogs"
  assume_role_policy = data.aws_iam_policy_document.firehose_assume.json
}

resource "aws_iam_policy" "firehose_waf_logs" {
  name   = "FirehoseWafLogsPolicy"
  path   = "/"
  policy = data.aws_iam_policy_document.firehose_waf_logs.json
}

resource "aws_iam_role_policy_attachment" "firehose_waf_logs" {
  role       = aws_iam_role.firehose_waf_logs.name
  policy_arn = aws_iam_policy.firehose_waf_logs.arn
}

data "aws_iam_policy_document" "firehose_assume" {
  statement {
    actions = ["sts:AssumeRole"]
    effect  = "Allow"
    principals {
      type        = "Service"
      identifiers = ["firehose.amazonaws.com"]
    }
  }
}

data "aws_iam_policy_document" "firehose_waf_logs" {
  statement {
    effect = "Allow"
    actions = [
      "s3:AbortMultipartUpload",
      "s3:GetBucketLocation",
      "s3:GetObject",
      "s3:ListBucket",
      "s3:ListBucketMultipartUploads",
      "s3:PutObject"
    ]
    resources = [
      local.cbs_satellite_bucket_arn,
      "${local.cbs_satellite_bucket_arn}/*"
    ]
  }
  statement {
    effect = "Allow"
    actions = [
      "iam:CreateServiceLinkedRole"
    ]
    resources = [
      "arn:aws:iam::*:role/aws-service-role/wafv2.amazonaws.com/AWSServiceRoleForWAFV2Logging"
    ]
  }
}
