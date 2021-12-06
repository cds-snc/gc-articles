output "s3_bucket_regional_domain_name" {
  value = module.wordpress_storage.s3_bucket_regional_domain_name
}

output "s3_cloudfront_origin_access_identity_iam_arn" {
  value = aws_cloudfront_origin_access_identity.origin_access_identity.iam_arn
}
