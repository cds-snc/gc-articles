resource "random_string" "random" {
  length  = 6
  special = false
  upper   = false
}

resource "aws_secretsmanager_secret" "list_manager_api_key" {
  name = "list_manager_api_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "list_manager_api_key" {
  secret_id     = aws_secretsmanager_secret.list_manager_api_key.id
  secret_string = var.list_manager_api_key
}

resource "aws_secretsmanager_secret" "list_manager_endpoint" {
  name = "list_manager_endpoint_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "list_manager_endpoint" {
  secret_id     = aws_secretsmanager_secret.list_manager_endpoint.id
  secret_string = var.list_manager_endpoint
}

resource "aws_secretsmanager_secret" "list_manager_service_id" {
  name = "list_manager_service_id_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "list_manager_service_id" {
  secret_id     = aws_secretsmanager_secret.list_manager_service_id.id
  secret_string = var.list_manager_service_id
}

resource "aws_secretsmanager_secret" "notify_api_key" {
  name = "notify_api_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "notify_api_key" {
  secret_id     = aws_secretsmanager_secret.notify_api_key.id
  secret_string = var.notify_api_key
}

resource "aws_secretsmanager_secret" "wordpress_auth_key" {
  name = "wordpress_auth_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_auth_key" {
  secret_id     = aws_secretsmanager_secret.wordpress_auth_key.id
  secret_string = var.wordpress_auth_key
}

resource "aws_secretsmanager_secret" "wordpress_secure_auth_key" {
  name = "wordpress_secure_auth_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_secure_auth_key" {
  secret_id     = aws_secretsmanager_secret.wordpress_secure_auth_key.id
  secret_string = var.wordpress_secure_auth_key
}

resource "aws_secretsmanager_secret" "wordpress_logged_in_key" {
  name = "wordpress_logged_in_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_logged_in_key" {
  secret_id     = aws_secretsmanager_secret.wordpress_logged_in_key.id
  secret_string = var.wordpress_logged_in_key
}

resource "aws_secretsmanager_secret" "wordpress_nonce_key" {
  name = "wordpress_nonce_key_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_nonce_key" {
  secret_id     = aws_secretsmanager_secret.wordpress_nonce_key.id
  secret_string = var.wordpress_nonce_key
}

resource "aws_secretsmanager_secret" "wordpress_auth_salt" {
  name = "wordpress_auth_salt_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_auth_salt" {
  secret_id     = aws_secretsmanager_secret.wordpress_auth_salt.id
  secret_string = var.wordpress_auth_salt
}

resource "aws_secretsmanager_secret" "wordpress_secure_auth_salt" {
  name = "wordpress_secure_auth_salt_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_secure_auth_salt" {
  secret_id     = aws_secretsmanager_secret.wordpress_secure_auth_salt.id
  secret_string = var.wordpress_secure_auth_salt
}

resource "aws_secretsmanager_secret" "wordpress_logged_in_salt" {
  name = "wordpress_logged_in_salt_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_logged_in_salt" {
  secret_id     = aws_secretsmanager_secret.wordpress_logged_in_salt.id
  secret_string = var.wordpress_logged_in_salt
}

resource "aws_secretsmanager_secret" "wordpress_nonce_salt" {
  name = "wordpress_nonce_salt_${random_string.random.result}"
}

resource "aws_secretsmanager_secret_version" "wordpress_nonce_salt" {
  secret_id     = aws_secretsmanager_secret.wordpress_nonce_salt.id
  secret_string = var.wordpress_nonce_salt
}
