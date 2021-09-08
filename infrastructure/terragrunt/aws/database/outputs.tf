output "database_host_secret_arn" {
  value = aws_secretsmanager_secret_version.database_host.arn
}

output "database_name_secret_arn" {
  value = aws_secretsmanager_secret_version.database_name.arn
}

output "database_username_secret_arn" {
  value = aws_secretsmanager_secret_version.database_username.arn
}

output "database_password_secret_arn" {
  value = aws_secretsmanager_secret_version.database_password.arn
}

output "database_proxy_security_group_id" {
  value = module.rds_cluster.proxy_security_group_id
}

output "rds_cluster_id" {
  value = module.rds_cluster.rds_cluster_id
}
