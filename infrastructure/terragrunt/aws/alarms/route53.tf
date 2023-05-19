resource "aws_route53_health_check" "wordpress" {
  fqdn              = var.healthcheck_domain
  port              = 443
  type              = "HTTPS"
  resource_path     = var.healthcheck_path
  failure_threshold = "3"
  request_interval  = "30"
  regions           = ["us-east-1", "us-west-1", "us-west-2"]

  tags = {
    "Name"       = "wordpress"
    "CostCentre" = var.billing_code
  }
}