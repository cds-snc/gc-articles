locals {
  docker_deploy = "gc-articles-docker-deploy"
}

module "docker_deploy" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v10.11.4"
  billing_tag_value = var.billing_tag_value
  oidc_exists       = true
  roles = [
    {
      name      = local.docker_deploy
      repo_name = "gc-articles"
      claim     = "ref:refs/heads/main"
    }
  ]
}

resource "aws_iam_role_policy_attachment" "docker_deploy" {
  role       = local.docker_deploy
  policy_arn = aws_iam_policy.docker_deploy.arn
  depends_on = [
    module.docker_deploy
  ]
}

resource "aws_iam_policy" "docker_deploy" {
  name   = local.docker_deploy
  path   = "/"
  policy = data.aws_iam_policy_document.docker_deploy.json
}

#trivy:ignore:AVD-AWS-0342
data "aws_iam_policy_document" "docker_deploy" {
  # ECS task definition management
  statement {
    sid    = "ECSTaskDefinition"
    effect = "Allow"
    actions = [
      "ecs:DescribeTaskDefinition",
      "ecs:RegisterTaskDefinition",
      "ecs:TagResource",
    ]
    resources = ["*"]
  }

  # ECS service update
  statement {
    sid    = "ECSService"
    effect = "Allow"
    actions = [
      "ecs:DescribeClusters",
      "ecs:DescribeServices",
      "ecs:UpdateService",
    ]
    resources = ["*"]
  }

  # Pass the ECS task execution role when registering new task definitions
  statement {
    sid    = "IAMPassRole"
    effect = "Allow"
    actions = [
      "iam:PassRole",
    ]
    resources = ["arn:aws:iam::${var.account_id}:role/${var.cluster_name}-ecs-task"]
  }

  # Terraform state backend — read/write state and locking
  statement {
    sid    = "TerraformStateReadWrite"
    effect = "Allow"
    actions = [
      "s3:GetObject",
      "s3:PutObject",
      "s3:DeleteObject",
    ]
    resources = ["arn:aws:s3:::platform-mvp-articles-${var.env}-tfstate/*"]
  }

  statement {
    sid    = "TerraformStateBucketList"
    effect = "Allow"
    actions = ["s3:ListBucket"]
    resources = ["arn:aws:s3:::platform-mvp-articles-${var.env}-tfstate"]
  }

  statement {
    sid    = "TerraformStateLock"
    effect = "Allow"
    actions = [
      "dynamodb:GetItem",
      "dynamodb:PutItem",
      "dynamodb:DeleteItem",
    ]
    resources = ["arn:aws:dynamodb:${var.region}:${var.account_id}:table/terraform-state-lock-dynamo"]
  }
}
