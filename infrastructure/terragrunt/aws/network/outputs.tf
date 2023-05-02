output "load_balancer_security_group_id" {
  value = aws_security_group.wordpress_load_balancer.id
}

output "ecs_events_lambda_security_group_id" {
  value = aws_security_group.ecs_events_lambda.id
}

output "ecs_service_security_group_id" {
  value = aws_security_group.wordpress_ecs.id
}

output "efs_security_group_id" {
  value = var.enable_efs ? aws_security_group.wordpress_efs[0].id : "empty"
}

output "private_subnet_ids" {
  value = module.wordpress_vpc.private_subnet_ids
}

output "public_subnet_ids" {
  value = module.wordpress_vpc.public_subnet_ids
}

output "vpc_id" {
  value = module.wordpress_vpc.vpc_id
}
