module "vpn" {
  source = "github.com/cds-snc/terraform-modules//client_vpn?ref=v9.6.6"

  endpoint_name       = "private_subnets"
  access_group_id     = var.client_vpn_access_group_id
  self_service_portal = "disabled"

  vpc_id              = module.wordpress_vpc.vpc_id
  vpc_cidr_block      = module.wordpress_vpc.cidr_block
  subnet_cidr_blocks  = module.wordpress_vpc.private_subnet_cidr_blocks
  subnet_ids          = []
  acm_certificate_arn = aws_acm_certificate.client_vpn.arn

  client_vpn_saml_metadata_document = var.client_vpn_saml_metadata

  billing_tag_value = var.billing_tag_value
}

#
# Certificate used for VPN communication
#
resource "tls_private_key" "client_vpn" {
  algorithm = "RSA"
  rsa_bits  = 2048
}

resource "tls_self_signed_cert" "client_vpn" {
  private_key_pem       = tls_private_key.client_vpn.private_key_pem
  validity_period_hours = 43800 # 5 years
  early_renewal_hours   = 672   # Generate new cert if Terraform is run within 4 weeks of expiry

  subject {
    common_name = "vpn.${var.env}.articles.alpha.canada.ca"
  }

  allowed_uses = [
    "key_encipherment",
    "digital_signature",
    "server_auth",
    "ipsec_end_system",
    "ipsec_tunnel",
    "any_extended",
    "cert_signing",
  ]
}

resource "aws_acm_certificate" "client_vpn" {
  private_key      = tls_private_key.client_vpn.private_key_pem
  certificate_body = tls_self_signed_cert.client_vpn.cert_pem

  tags = {
    Terraform             = true
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
  }
}