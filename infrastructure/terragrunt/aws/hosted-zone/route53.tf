resource "aws_route53_zone" "wordpress" {
  name = var.zone_name

  tags = var.common_tags
}
