#
# Lambda: post notifications to Slack
#
resource "aws_lambda_function" "notify_slack" {
  # checkov:skip=CKV_AWS_115:No function-level concurrent execution limit required
  # checkov:skip=CKV_AWS_116:No Dead Letter Queue required
  function_name = "notify_slack"
  description   = "Lambda function to post CloudWatch alarm notifications to a Slack channel."

  filename    = data.archive_file.notify_slack.output_path
  handler     = "notify_slack.lambda_handler"
  runtime     = "python3.8"
  timeout     = 30
  memory_size = 1024

  role             = aws_iam_role.notify_slack_lambda.arn
  source_code_hash = filebase64sha256(data.archive_file.notify_slack.output_path)

  environment {
    variables = {
      SLACK_WEBHOOK_URL = var.slack_webhook_url
      PROJECT_NAME      = "WordPress"
      LOG_EVENTS        = "True"
    }
  }

  depends_on = [
    aws_iam_role_policy_attachment.notify_slack_lambda,
    aws_cloudwatch_log_group.notify_slack_lambda,
  ]

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

data "archive_file" "notify_slack" {
  type        = "zip"
  source_file = "notify_slack/notify_slack.py"
  output_path = "/tmp/notify_slack.py.zip"
}

resource "aws_lambda_permission" "notify_slack" {
  statement_id  = "AllowExecutionFromSNS"
  action        = "lambda:InvokeFunction"
  function_name = aws_lambda_function.notify_slack.function_name
  principal     = "sns.amazonaws.com"
  source_arn    = aws_sns_topic.alert_warning.arn
}

resource "aws_lambda_permission" "notify_slack_us_east" {
  statement_id  = "AllowExecutionFromSNSUsEast"
  action        = "lambda:InvokeFunction"
  function_name = aws_lambda_function.notify_slack.function_name
  principal     = "sns.amazonaws.com"
  source_arn    = aws_sns_topic.alert_warning_us_east.arn
}

#
# CloudWatch: Lambda logs
#
resource "aws_cloudwatch_log_group" "notify_slack_lambda" {
  name              = "/aws/lambda/notify_slack"
  retention_in_days = "14"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}
