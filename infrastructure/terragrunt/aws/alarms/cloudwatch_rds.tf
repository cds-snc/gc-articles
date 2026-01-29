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
