resource "aws_route53_zone" "wordpress" {
  name = var.zone_name

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}
