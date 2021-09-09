#
# Kinesis Firehose
#
resource "aws_kinesis_firehose_delivery_stream" "firehose_waf_logs" {
  name        = "aws-waf-logs-platform-ircc"
  destination = "s3"

  server_side_encryption {
    enabled = true
  }

  s3_configuration {
    role_arn   = aws_iam_role.firehose_waf_logs.arn
    bucket_arn = module.firehose_waf_log_bucket.s3_bucket_arn
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_kinesis_firehose_delivery_stream" "firehose_waf_logs_us_east" {
  provider = aws.us-east-1

  name        = "aws-waf-logs-platform-ircc-us-east"
  destination = "s3"

  server_side_encryption {
    enabled = true
  }

  s3_configuration {
    role_arn   = aws_iam_role.firehose_waf_logs.arn
    bucket_arn = module.firehose_waf_log_bucket.s3_bucket_arn
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
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
        days = 90
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
      module.firehose_waf_log_bucket.s3_bucket_arn,
      "${module.firehose_waf_log_bucket.s3_bucket_arn}/*"
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
