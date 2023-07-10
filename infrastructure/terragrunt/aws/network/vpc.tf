#
# VPC: 3 public + 3 private subnets across 3 availability zones
#
module "wordpress_vpc" {
  source = "github.com/cds-snc/terraform-modules//vpc?ref=v0.0.31"
  name   = "wordpress"

  high_availability = true
  enable_flow_log   = true

  allow_https_request_out          = true
  allow_https_request_out_response = true
  allow_https_request_in           = true
  allow_https_request_in_response  = true

  billing_tag_key   = var.billing_tag_key
  billing_tag_value = var.billing_tag_value
}

resource "aws_flow_log" "cloud_based_sensor" {
  log_destination      = "arn:aws:s3:::${var.cbs_satellite_bucket_name}/vpc_flow_logs/"
  log_destination_type = "s3"
  traffic_type         = "ALL"
  vpc_id               = module.wordpress_vpc.vpc_id
  log_format           = "$${vpc-id} $${version} $${account-id} $${interface-id} $${srcaddr} $${dstaddr} $${srcport} $${dstport} $${protocol} $${packets} $${bytes} $${start} $${end} $${action} $${log-status} $${subnet-id} $${instance-id}"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
    Terraform             = true
  }
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
