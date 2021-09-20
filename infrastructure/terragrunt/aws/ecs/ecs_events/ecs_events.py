"""
Lambda function that is invoked when an ECS event is fired and writes
that event to a CloudWatch log group.
"""
import json

def lambda_handler(event, context):
    print(json.dumps(event))