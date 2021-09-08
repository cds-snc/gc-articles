resource "random_string" "random" {
  length  = 6
  special = false
  upper   = false
}

resource "aws_secretsmanager_secret" "database_host" {
  name = "wordpress_database_host_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "database_host" {
  secret_id     = aws_secretsmanager_secret.database_host.id
  secret_string = module.rds_cluster.proxy_endpoint
}

resource "aws_secretsmanager_secret" "database_name" {
  name = "wordpress_database_name_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "database_name" {
  secret_id     = aws_secretsmanager_secret.database_name.id
  secret_string = var.database_name
}

resource "aws_secretsmanager_secret" "database_username" {
  name = "wordpress_database_username_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "database_username" {
  secret_id     = aws_secretsmanager_secret.database_username.id
  secret_string = var.database_username
}

resource "aws_secretsmanager_secret" "database_password" {
  name = "wordpress_database_password_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "database_password" {
  secret_id     = aws_secretsmanager_secret.database_password.id
  secret_string = var.database_password
}
