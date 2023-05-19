resource "aws_shield_protection" "alb" {
  name         = "LoadBalancer"
  resource_arn = var.alb_arn

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_shield_protection" "cloudfront" {
  name         = "CloudFront"
  resource_arn = var.cloudfront_arn

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_shield_protection" "route53_hosted_zone" {
  name         = "Route53HostedZone"
  resource_arn = "arn:aws:route53:::hostedzone/${var.hosted_zone_id}"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_shield_protection_health_check_association" "cloudfront" {
  health_check_arn     = aws_route53_health_check.wordpress.arn
  shield_protection_id = aws_shield_protection.cloudfront.id
}
