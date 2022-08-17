#
# Synthetic canary
#
resource "aws_synthetics_canary" "wordpress" {
  name                 = "wordpress-healthcheck"
  artifact_s3_location = "s3://${aws_s3_bucket.synthetic_artifacts.id}/wordpress-healthcheck/"
  execution_role_arn   = aws_iam_role.synthetic_canary_execution_role.arn
  zip_file             = data.archive_file.wordpress_canary.output_path
  handler              = "healthcheck.handler"
  runtime_version      = "syn-nodejs-puppeteer-3.6"
  start_canary         = true

  schedule {
    expression = "rate(5 minutes)"
  }
}

resource "random_uuid" "wordpress_canary" {
  keepers = {
    "template" : sha256(data.template_file.wordpress_canary.rendered)
  }
}

data "template_file" "wordpress_canary" {
  template = file("canary/healthcheck.tmpl.js")
  vars = {
    healthcheck_url_eng = var.canary_healthcheck_url_eng
    healthcheck_url_fra = var.canary_healthcheck_url_fra
  }
}

data "archive_file" "wordpress_canary" {
  type = "zip"
  source {
    content  = data.template_file.wordpress_canary.rendered
    filename = "nodejs/node_modules/healthcheck.js"
  }
  output_path = "${random_uuid.wordpress_canary.result}-canary.js.zip"
}

#
# S3 bucket for canary artifacts
# TODO: switch to cds-snc S3 module
resource "aws_s3_bucket" "synthetic_artifacts" {

  # checkov:skip=CKV_AWS_18: Access logging not required for ephemeral data
  # checkov:skip=CKV_AWS_21: Verioning not needed for ephemeral data
  # checkov:skip=CKV_AWS_52: MFA delete not needed for ephemeral data
  # checkov:skip=CKV_AWS_144: Cross-region replication not needed for ephemeral data

  bucket = "platform-mvp-synthetic-canary-${var.env}"
  acl    = "private"
  server_side_encryption_configuration {
    rule {
      apply_server_side_encryption_by_default {
        sse_algorithm = "AES256"
      }
    }
  }
  lifecycle_rule {
    enabled = true
    expiration {
      days = 31
    }
  }
}

resource "aws_s3_bucket_public_access_block" "synthetic_artifacts" {
  bucket = aws_s3_bucket.synthetic_artifacts.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

#
# Canary execution role and policy
#
resource "aws_iam_role" "synthetic_canary_execution_role" {
  name               = "SyntheticCanaryExecutionRole"
  assume_role_policy = data.aws_iam_policy_document.synthetic_canary_assume_role_policy.json
}

resource "aws_iam_policy" "synthetic_canary_policy" {
  name   = "SyntheticCanaryPolicy"
  path   = "/"
  policy = data.aws_iam_policy_document.synthetic_canary_policy.json
}

resource "aws_iam_role_policy_attachment" "synthetic_canary_policy_attachment" {
  role       = aws_iam_role.synthetic_canary_execution_role.name
  policy_arn = aws_iam_policy.synthetic_canary_policy.arn
}

data "aws_iam_policy_document" "synthetic_canary_assume_role_policy" {
  statement {
    effect = "Allow"
    actions = [
      "sts:AssumeRole"
    ]
    principals {
      type = "Service"
      identifiers = [
        "lambda.amazonaws.com"
      ]
    }
  }
}

data "aws_iam_policy_document" "synthetic_canary_policy" {
  statement {
    effect = "Allow"
    actions = [
      "s3:PutObject",
      "s3:GetBucketLocation"
    ]
    resources = [
      aws_s3_bucket.synthetic_artifacts.arn,
      "${aws_s3_bucket.synthetic_artifacts.arn}/*"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "logs:CreateLogStream",
      "logs:PutLogEvents",
      "logs:CreateLogGroup"
    ]
    resources = [
      "arn:aws:logs:${var.region}:${var.account_id}:log-group:/aws/lambda/cwsyn-${aws_synthetics_canary.wordpress.name}-*"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "s3:ListAllMyBuckets",
      "xray:PutTraceSegments"
    ]
    resources = [
      "*"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "cloudwatch:PutMetricData"
    ]
    resources = [
      "*"
    ]
    condition {
      test     = "StringEquals"
      variable = "cloudwatch:namespace"
      values = [
        "CloudWatchSynthetics"
      ]
    }
  }
}
