variable "allow_wordpress_uploads" {
  description = "Should WordPress media uploads be allowed.  This controls rule exclusions from the WAF AWSManagedRulesCommonRuleSet."
  type        = bool
}

variable "cloudfront_custom_header_name" {
  description = "Custom header name added by CloudFront.  Used to block direct user requests to the ALB."
  type        = string
  sensitive   = true
}

variable "cloudfront_custom_header_value" {
  description = "Custom header value added by CloudFront.  Used to block direct user requests to the ALB."
  type        = string
  sensitive   = true
}

variable "domain_name" {
  description = "Domain name for the load balancer, certificate and CloudFront"
  type        = string
}

variable "load_balancer_security_group_id" {
  description = "ID of the security group to attach to the load balancer"
  type        = string
}

variable "public_subnet_ids" {
  description = "Public subnet IDs to attach the load balancer to"
  type        = list(any)
}

variable "vpc_id" {
  description = "VPC ID for the load balancer"
  type        = string
}

variable "zone_id" {
  description = "Hosted zone ID for the DNS records"
  type        = string
}
