#
# Events: publish ECS tasks warn/error events to an SNS topic
#         an associated CloudWatch alarm will watch for these events.
#
locals {
  ecs_service_arn = "arn:aws:ecs:${var.region}:${var.account_id}:service/${var.ecs_cluster_name}/${var.ecs_service_name}"
}
resource "aws_cloudwatch_event_rule" "ecs_task_warn_error" {
  name        = "EcsTaskWarnError"
  description = "ECS task warn and error events"

  event_pattern = jsonencode({
    "source" : [
      "aws.ecs"
    ],
    "detail-type" : [
      "ECS Service Action"
    ],
    "resources" : [
      "${local.ecs_service_arn}"
    ],
    "detail" : {
      "eventType" : ["WARN", "ERROR"]
    }
  })
}

resource "aws_cloudwatch_event_target" "sns" {
  rule = aws_cloudwatch_event_rule.ecs_task_warn_error.name
  arn  = aws_sns_topic.cloudwatch_events.arn
}
