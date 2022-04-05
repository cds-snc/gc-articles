locals {
  s3_origin_id = "wordpress_uploads"
}

resource "aws_cloudfront_distribution" "wordpress" {
  origin {
    domain_name = aws_lb.wordpress.dns_name
    origin_id   = aws_lb.wordpress.name

    custom_header {
      name  = var.cloudfront_custom_header_name
      value = var.cloudfront_custom_header_value
    }

    custom_origin_config {
      http_port              = 80
      https_port             = 443
      origin_protocol_policy = "https-only"
      origin_ssl_protocols   = ["TLSv1.2"]
    }
  }

  origin {
    domain_name = var.s3_bucket_regional_domain_name
    origin_id   = local.s3_origin_id

    s3_origin_config {
      origin_access_identity = var.s3_cloudfront_origin_access_identity_path
    }
  }

  enabled         = true
  is_ipv6_enabled = true
  web_acl_id      = aws_wafv2_web_acl.wordpress_waf.arn

  aliases = [var.domain_name]

  default_cache_behavior {
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Options", "Referer", "Authorization"]
      cookies {
        forward = "whitelist"
        whitelisted_names = [
          "comment_author_*",
          "comment_author_email_*",
          "comment_author_url_*",
          "wordpress_*",
          "wp-*"
        ]
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 1
    default_ttl            = 86400
    max_ttl                = 31536000
    compress               = true

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_frontend.id
  }

  ordered_cache_behavior {
    path_pattern     = "/sign-in-se-connecter*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Origin", "User-Agent"]
      cookies {
        forward = "all"
      }
    }

    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
    compress               = true
    viewer_protocol_policy = "redirect-to-https"

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_frontend.id
  }

  ordered_cache_behavior {
    path_pattern     = "*/wp-admin/*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Origin", "User-Agent"]
      cookies {
        forward = "all"
      }
    }

    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
    compress               = true
    viewer_protocol_policy = "redirect-to-https"

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_admin.id
  }

  ordered_cache_behavior {
    path_pattern     = "wp-admin/*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Origin", "User-Agent"]
      cookies {
        forward = "all"
      }
    }

    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
    compress               = true
    viewer_protocol_policy = "redirect-to-https"

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_admin.id
  }

  ordered_cache_behavior {
    path_pattern     = "/uploads/*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = local.s3_origin_id

    forwarded_values {
      query_string = false
      headers      = ["Origin"]

      cookies {
        forward = "none"
      }
    }

    min_ttl                = 0
    default_ttl            = 86400
    max_ttl                = 31536000
    compress               = true
    viewer_protocol_policy = "redirect-to-https"
  }

  ordered_cache_behavior {
    path_pattern     = "*/wp-json/*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Origin", "User-Agent", "Authorization"]
      cookies {
        forward = "all"
      }
    }

    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
    compress               = true
    viewer_protocol_policy = "redirect-to-https"

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_api.id
  }

  ordered_cache_behavior {
    path_pattern     = "wp-json/*"
    allowed_methods  = ["GET", "HEAD", "OPTIONS", "DELETE", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD", "OPTIONS"]
    target_origin_id = aws_lb.wordpress.name

    forwarded_values {
      query_string = true
      headers      = ["Host", "Origin", "User-Agent", "Authorization"]
      cookies {
        forward = "all"
      }
    }

    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
    compress               = true
    viewer_protocol_policy = "redirect-to-https"

    response_headers_policy_id = aws_cloudfront_response_headers_policy.security_headers_policy_api.id
  }

  price_class = "PriceClass_200"

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  viewer_certificate {
    acm_certificate_arn      = aws_acm_certificate_validation.wordpress_cloudfront.certificate_arn
    minimum_protocol_version = "TLSv1.2_2021"
    ssl_support_method       = "sni-only"
  }

  logging_config {
    include_cookies = false
    bucket          = aws_s3_bucket.cloudfront_logs.bucket_domain_name
    prefix          = "cloudfront"
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_iam_user" "cache_buster" {
  name = "cache_buster"
}

data "aws_iam_policy_document" "cache_buster" {
  statement {
    effect = "Allow"
    actions = [
      "cloudfront:GetDistribution",
      "cloudfront:ListInvalidations",
      "cloudfront:GetDistributionConfig",
      "cloudfront:GetInvalidation",
      "cloudfront:CreateInvalidation"
    ]
    resources = [
      aws_cloudfront_distribution.wordpress.arn
    ]
  }
}

resource "aws_iam_policy" "cache_buster" {
  name   = "cache_buster"
  policy = data.aws_iam_policy_document.cache_buster.json
}

resource "aws_iam_user_policy_attachment" "cache_buster" {
  #checkov:skip=CKV_AWS_40:This is a one-off user for cache-busting plugin
  user       = aws_iam_user.cache_buster.name
  policy_arn = aws_iam_policy.cache_buster.arn
}
