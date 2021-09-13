#
# VPC: 3 public + 3 private subnets across 3 availability zones
#
module "wordpress_vpc" {
  source = "github.com/cds-snc/terraform-modules?ref=v0.0.31//vpc"
  name   = "wordpress"

  high_availability = true
  enable_flow_log   = true

  billing_tag_key   = var.billing_tag_key
  billing_tag_value = var.billing_tag_value
}

resource "aws_network_acl_rule" "allow_all_ingress" {
  network_acl_id = module.wordpress_vpc.main_nacl_id
  rule_number    = 100
  egress         = false
  protocol       = "-1"
  rule_action    = "allow"
  cidr_block     = "0.0.0.0/0"
  from_port      = 0
  to_port        = 0
}

resource "aws_network_acl_rule" "allow_all_egress" {
  network_acl_id = module.wordpress_vpc.main_nacl_id
  rule_number    = 101
  egress         = true
  protocol       = "-1"
  rule_action    = "allow"
  cidr_block     = "0.0.0.0/0"
  from_port      = 0
  to_port        = 0
}

resource "aws_vpc_endpoint" "ecr-dkr" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.ecr.dkr"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "ecr-api" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.ecr.api"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "secretsmanager" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.secretsmanager"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "logs" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.logs"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "monitoring" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.monitoring"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "sns" {
  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.sns"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

resource "aws_vpc_endpoint" "efs" {
  count = var.enable_efs ? 1 : 0

  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_endpoint_type   = "Interface"
  service_name        = "com.amazonaws.${var.region}.elasticfilesystem"
  private_dns_enabled = true
  security_group_ids = [
    aws_security_group.vpc_endpoint.id,
  ]
  subnet_ids = module.wordpress_vpc.private_subnet_ids
}

data "aws_vpc" "wordpress_vpc" {
  id = module.wordpress_vpc.vpc_id
}

resource "aws_vpc_endpoint" "s3" {
  vpc_id            = module.wordpress_vpc.vpc_id
  vpc_endpoint_type = "Gateway"
  service_name      = "com.amazonaws.${var.region}.s3"
  route_table_ids   = [data.aws_vpc.wordpress_vpc.main_route_table_id]
}
