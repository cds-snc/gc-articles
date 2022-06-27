output "alb_arn" {
  value = aws_lb.wordpress.arn
}

output "alb_arn_suffix" {
  value = aws_lb.wordpress.arn_suffix
}

output "alb_target_group_arn" {
  value = aws_lb_target_group.wordpress.arn
}

output "alb_target_group_arn_suffix" {
  value = aws_lb_target_group.wordpress.arn_suffix
}

output "cloudfront_arn" {
  value = aws_cloudfront_distribution.wordpress.arn
}

output "cloudfront_distribution_id" {
  value = aws_cloudfront_distribution.wordpress.id
}

output "cloudfront_waf_web_acl_name" {
  value = var.enable_waf ? aws_wafv2_web_acl.wordpress_waf[0].name : "n/a"
}

output "domain_name" {
  value = var.domain_name
}
