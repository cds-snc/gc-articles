#
# CloudFront logs
# TODO: switch to the cds-snc S3 module
data "aws_canonical_user_id" "current" {}

resource "aws_s3_bucket" "cloudfront_logs" {
  # checkov:skip=CKV_AWS_18:access logging not required for ephemeral data
  # checkov:skip=CKV_AWS_21:verioning not needed for ephemeral data
  # checkov:skip=CKV_AWS_52:MFA delete not needed for ephemeral data
  # checkov:skip=CKV_AWS_144:cross-region replication not needed for ephemeral data

  bucket = "wordpress-fargate-${var.env}-cloudfront-logs"
  server_side_encryption_configuration {
    rule {
      apply_server_side_encryption_by_default {
        sse_algorithm = "AES256"
      }
    }
  }

}
resource "aws_s3_bucket_acl" "cloudfront_logs" {
  bucket = aws_s3_bucket.cloudfront_logs.id

  access_control_policy {
    grant {
      grantee {
        id   = "c4c1ede66af53448b93c283ce9448c4ba468c9432aa01d700d3878632f77d2d0"
        type = "CanonicalUser"
      }
      permission = "FULL_CONTROL"
    }

    owner {
      id = data.aws_canonical_user_id.current.id
    }
  }
}

resource "aws_s3_bucket_public_access_block" "cloudfront_logs" {
  bucket = aws_s3_bucket.cloudfront_logs.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_lifecycle_configuration" "cloudfront_logs" {
  bucket = aws_s3_bucket.cloudfront_logs.id

  rule {
    id     = "expire-objects"
    status = "Enabled"

    expiration {
      days = 30
    }

    noncurrent_version_expiration {
      noncurrent_days = 30
    }
  }
}
