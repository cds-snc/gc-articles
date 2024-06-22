#
# RDS MySQL cluster across 3 subnets
#
module "rds_cluster" {
  source = "github.com/cds-snc/terraform-modules//rds?ref=v9.4.11"
  name   = "wordpress"

  database_name  = var.database_name
  engine         = "aurora-mysql"
  engine_version = "8.0.mysql_aurora.3.06.0"
  instances      = var.database_instances_count
  instance_class = var.database_instance_class
  username       = var.database_username
  password       = var.database_password

  # Enable audit logging
  db_cluster_parameter_group_name = aws_rds_cluster_parameter_group.enable_audit_logging_v8.name
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

resource "aws_rds_cluster_parameter_group" "enable_audit_logging_v8" {
  name        = "wordpress-aurora-mysql8"
  family      = "aurora-mysql8.0"
  description = "RDS cluster parameter group with audit logging enabled for MySQL 8.0"

  parameter {
    name         = "binlog_format"
    value        = "ROW"
    apply_method = "pending-reboot"
  }

  parameter {
    name         = "server_audit_logging"
    value        = "1"
    apply_method = "immediate"
  }

  parameter {
    name         = "server_audit_events"
    value        = "CONNECT,QUERY_DCL,QUERY_DDL,QUERY_DML"
    apply_method = "immediate"
  }
}

resource "aws_security_group_rule" "client_vpn_ingress_database" {
  description              = "Client VPN ingress to the database"
  type                     = "ingress"
  from_port                = 3306
  to_port                  = 3306
  protocol                 = "tcp"
  source_security_group_id = var.client_vpn_security_group_id
  security_group_id        = module.rds_cluster.proxy_security_group_id
}
