resource "aws_s3_bucket" "cloudfront_logs" {

  # checkov:skip=CKV_AWS_18:access logging not required for ephemeral data
  # checkov:skip=CKV_AWS_21:verioning not needed for ephemeral data
  # checkov:skip=CKV_AWS_52:MFA delete not needed for ephemeral data
  # checkov:skip=CKV_AWS_145:encryption with default S3 service key is acceptable
  # checkov:skip=CKV_AWS_144:cross-region replication not needed for ephemeral data

  bucket = "wordpress-fargate-${var.env}-cloudfront-logs"
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
      days = 90
    }
  }

  # awslogsdelivery account needs full control for cloudfront logging
  # https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/AccessLogs.html
  grant {
    id          = "c4c1ede66af53448b93c283ce9448c4ba468c9432aa01d700d3878632f77d2d0"
    type        = "CanonicalUser"
    permissions = ["FULL_CONTROL"]
  }
}

resource "aws_s3_bucket_public_access_block" "cloudfront_logs" {
  bucket = aws_s3_bucket.cloudfront_logs.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}
