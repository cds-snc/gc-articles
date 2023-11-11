#
# RDS MySQL cluster across 3 subnets
#
module "rds_cluster" {
  source = "github.com/cds-snc/terraform-modules//rds?ref=v7.3.2"
  name   = "wordpress"

  database_name  = var.database_name
  engine         = "aurora-mysql"
  engine_version = "5.7.mysql_aurora.2.11.2"
  instances      = var.database_instances_count
  instance_class = var.database_instance_class
  username       = var.database_username
  password       = var.database_password

  # Enable audit logging
  db_cluster_parameter_group_name = aws_rds_cluster_parameter_group.enable_audit_logging.name
  enabled_cloudwatch_logs_exports = ["audit"]

  backtrack_window             = 0 # Backtracking cannot be enabled on existing clusters :(
  backup_retention_period      = 14
  preferred_backup_window      = "02:00-04:00"
  performance_insights_enabled = var.database_performance_insights_enabled

  vpc_id     = var.vpc_id
  subnet_ids = var.private_subnet_ids

  billing_tag_key   = var.billing_tag_key
  billing_tag_value = var.billing_tag_value
}

resource "aws_rds_cluster_parameter_group" "enable_audit_logging" {
  name        = "wordpress-aurora-mysql57"
  family      = "aurora-mysql5.7"
  description = "RDS cluster parameter group with audit logging enabled"

  parameter {
    name  = "server_audit_logging"
    value = "1"
  }

  # Available events: https://docs.aws.amazon.com/AmazonRDS/latest/AuroraUserGuide/AuroraMySQL.Auditing.html#AuroraMySQL.Auditing.Enable.server_audit_events
  parameter {
    name  = "server_audit_events"
    value = "CONNECT,QUERY_DCL,QUERY_DDL,QUERY_DML"
  }
}
