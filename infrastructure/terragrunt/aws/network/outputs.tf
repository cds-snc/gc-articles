output "load_balancer_security_group_id" {
  value = aws_security_group.wordpress_load_balancer.id
}

output "ecs_service_security_group_id" {
  value = aws_security_group.wordpress_ecs.id
}

output "efs_security_group_id" {
  value = aws_security_group.wordpress_efs.id
}

output "private_subnet_ids" {
  value = module.wordpress_vpc.private_subnet_ids
}

output "public_subnet_ids" {
  value = module.wordpress_vpc.public_subnet_ids
}

output "sns_lambda_security_group_id" {
  value = aws_security_group.sns_lambda.id
}

output "vpc_id" {
  value = module.wordpress_vpc.vpc_id
}
