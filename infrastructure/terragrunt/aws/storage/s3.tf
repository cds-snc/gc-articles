module "wordpress_storage" {
  source            = "github.com/cds-snc/terraform-modules?ref=v0.0.36//S3"
  bucket_name       = "platform-gc-articles-${var.env}-uploads"
  billing_tag_value = var.billing_tag_value
}

resource "aws_iam_user" "wordpress_storage" {
  name = "wordpress_storage"
}

resource "aws_s3_bucket_policy" "wordpress_storage" {
  bucket = module.wordpress_storage.s3_bucket_id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "PublicReadGetObject"
        Effect    = "Allow"
        Principal = "*"
        "Action" : [
          "s3:GetObject"
        ],
        Resource = [
          "${module.wordpress_storage.s3_bucket_arn}/*",
        ]
      },
    ]
  })
}

resource "aws_iam_user_policy" "wordpress_storage" {
  name = "wordpress_storage"
  user = aws_iam_user.wordpress_storage.name

  #checkov:skip=CKV_AWS_40:This is a one-off user for s3-storage plugin
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = [
          "s3:AbortMultipartUpload",
          "s3:DeleteObject",
          "s3:GetBucketAcl",
          "s3:GetBucketLocation",
          "s3:GetBucketPolicy",
          "s3:GetObject",
          "s3:GetObjectAcl",
          "s3:ListBucket",
          "s3:ListBucketMultipartUploads",
          "s3:ListMultipartUploadParts",
          "s3:PutObject",
          "s3:PutObjectAcl"
        ]
        Effect   = "Allow"
        Resource = "${module.wordpress_storage.s3_bucket_arn}/*"
      },
      {
        "Action" : ["s3:ListBucket"],
        "Effect" : "Allow",
        "Resource" : [module.wordpress_storage.s3_bucket_arn],
        "Condition" : { "StringLike" : { "s3:prefix" : ["*"] } }
      }
    ]
  })
}
