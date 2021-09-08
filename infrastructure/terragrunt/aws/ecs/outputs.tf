output "ecs_cloudfront_log_group_name" {
  value = aws_cloudwatch_log_group.wordpress_ecs_logs.name
}

output "ecs_cluster_name" {
  value = aws_ecs_cluster.wordpress.name
}

output "ecs_service_name" {
  value = aws_ecs_service.wordpress_service.name
}

output "efs_id" {
  value = var.enable_efs ? aws_efs_file_system.wordpress[0].id : "n/a"
}
