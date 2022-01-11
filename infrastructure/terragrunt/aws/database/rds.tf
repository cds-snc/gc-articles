#
# RDS MySQL cluster across 3 subnets
#
module "rds_cluster" {
  source = "github.com/cds-snc/terraform-modules?ref=v0.0.47//rds"
  name   = "wordpress"

  database_name  = var.database_name
  engine         = "aurora-mysql"
  engine_version = "5.7.mysql_aurora.2.10.0"
  instances      = var.database_instances_count
  instance_class = var.database_instance_class
  username       = var.database_username
  password       = var.database_password

  backup_retention_period      = 14
  preferred_backup_window      = "02:00-04:00"
  performance_insights_enabled = var.database_performance_insights_enabled

  vpc_id     = var.vpc_id
  subnet_ids = var.private_subnet_ids

  billing_tag_key   = var.billing_tag_key
  billing_tag_value = var.billing_tag_value
}
