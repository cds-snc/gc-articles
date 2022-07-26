resource "aws_acm_certificate" "wordpress" {
  domain_name               = var.domain_name
  subject_alternative_names = ["*.${var.domain_name}"]
  validation_method         = "DNS"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
  }
}

# CloudFront certificate must be in us-east-1
resource "aws_acm_certificate" "wordpress_cloudfront" {
  provider = aws.us-east-1

  domain_name               = var.domain_name
  subject_alternative_names = ["*.${var.domain_name}"]
  validation_method         = "DNS"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_acm_certificate" "wordpress_new" {
  domain_name               = "articles.canada.ca"
  subject_alternative_names = ["*.articles.canada.ca"]
  validation_method         = "DNS"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
  }
}

# CloudFront certificate must be in us-east-1
resource "aws_acm_certificate" "wordpress_new_cloudfront" {
  provider = aws.us-east-1

  domain_name               = "articles.canada.ca"
  subject_alternative_names = ["*.articles.canada.ca"]
  validation_method         = "DNS"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_route53_record" "wordpress_validation" {
  zone_id = var.zone_id

  for_each = {
    for dvo in aws_acm_certificate.wordpress.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  allow_overwrite = true
  name            = each.value.name
  records         = [each.value.record]
  type            = each.value.type
  ttl             = 60
}

resource "aws_route53_record" "wordpress_validation_cloudfront" {
  zone_id = var.zone_id

  for_each = {
    for dvo in aws_acm_certificate.wordpress_cloudfront.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  allow_overwrite = true
  name            = each.value.name
  records         = [each.value.record]
  type            = each.value.type
  ttl             = 60
}

resource "aws_acm_certificate_validation" "wordpress" {
  certificate_arn         = aws_acm_certificate.wordpress.arn
  validation_record_fqdns = [for record in aws_route53_record.wordpress_validation : record.fqdn]
}

resource "aws_acm_certificate_validation" "wordpress_cloudfront" {
  provider                = aws.us-east-1
  certificate_arn         = aws_acm_certificate.wordpress_cloudfront.arn
  validation_record_fqdns = [for record in aws_route53_record.wordpress_validation_cloudfront : record.fqdn]
}
