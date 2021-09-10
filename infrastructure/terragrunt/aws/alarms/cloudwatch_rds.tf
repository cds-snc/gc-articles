#
# RDS alarms
# 
resource "aws_cloudwatch_metric_alarm" "rds_cpu_utilization_writer" {
  alarm_name          = "RDSCpuUtilizationWriter"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "CPUUtilization"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.rds_cpu_maxiumum

  alarm_description = "CPU utilization for RDS cluster writer in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "WRITER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_cpu_utilization_reader" {
  alarm_name          = "RDSCpuUtilizationReader"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "CPUUtilization"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.rds_cpu_maxiumum

  alarm_description = "CPU utilization for RDS cluster reader in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "READER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_aurora_replica_lag" {
  alarm_name          = "RdsAuroraReplicaLag"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "AuroraReplicaLag"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.rds_aurora_replica_lag_maximum

  alarm_description = "Replica lag (milliseconds) for RDS cluster reader in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "READER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_swap_usage_writer" {
  alarm_name          = "RdsSwapUsageWriter"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "SwapUsage"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.rds_swap_usage_maximum

  alarm_description = "Maximum swap usage (Bytes) for RDS cluster writer in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "WRITER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_swap_usage_reader" {
  alarm_name          = "RdsSwapUsageReader"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "SwapUsage"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.rds_swap_usage_maximum

  alarm_description = "Maximum swap usage (Bytes) for RDS cluster reader in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "READER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_freeable_memory_writer" {
  alarm_name          = "RdsFreeableMemoryWriter"
  comparison_operator = "LessThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "FreeableMemory"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Minimum"
  threshold           = var.rds_freeable_memory_minimum

  alarm_description = "Minimum freeable memory (Bytes) for RDS cluster writer in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "WRITER"
  }
}

resource "aws_cloudwatch_metric_alarm" "rds_freeable_memory_reader" {
  alarm_name          = "RdsFreeableMemoryReader"
  comparison_operator = "LessThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "FreeableMemory"
  namespace           = "AWS/RDS"
  period              = "300"
  statistic           = "Minimum"
  threshold           = var.rds_freeable_memory_minimum

  alarm_description = "Freeable memory (Bytes) for RDS cluster reader in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    DBClusterIdentifier = var.rds_cluster_id
    Role                = "READER"
  }
}
