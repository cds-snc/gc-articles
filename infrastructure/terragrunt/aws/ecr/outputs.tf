output "wordpress_repository_arn" {
  value = aws_ecr_repository.wordpress.arn
}

output "wordpress_repository_url" {
  value = aws_ecr_repository.wordpress.repository_url
}

output "apache_repository_arn" {
  value = aws_ecr_repository.apache.arn
}

output "apache_repository_url" {
  value = aws_ecr_repository.apache.repository_url
}
