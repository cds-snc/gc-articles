locals {
  docker_deploy = "gc-articles-docker-deploy"
}

module "docker_deploy" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v11.3.5"
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
  tags   = var.core_tags
}

data "aws_iam_policy_document" "docker_deploy" {
  statement {

    effect = "Allow"
    actions = [
      "ecs:DescribeTaskDefinition",
      "ecs:RegisterTaskDefinition",
      "ecs:TagResource",
    ]
    resources = ["*"]
  }
  statement {
    effect = "Allow"
    actions = [
      "ecs:DescribeClusters",
      "ecs:DescribeServices",
      "ecs:UpdateService",
    ]
    resources = [
      "arn:aws:ecs:${var.region}:${var.account_id}:cluster/${var.cluster_name}",
      "arn:aws:ecs:${var.region}:${var.account_id}:service/${var.cluster_name}/${var.cluster_name}",
    ]
  }
  statement {
    effect = "Allow"
    actions = [
      "iam:PassRole",
    ]
    resources = ["arn:aws:iam::${var.account_id}:role/${var.cluster_name}-ecs-task"]
  }
  statement {
    effect = "Allow"
    actions = [
      "s3:GetObject",
      "s3:PutObject",
      "s3:DeleteObject",
    ]
    resources = ["arn:aws:s3:::platform-mvp-articles-${var.env}-tfstate/*"]
  }

  statement {
    effect    = "Allow"
    actions   = ["s3:ListBucket"]
    resources = ["arn:aws:s3:::platform-mvp-articles-${var.env}-tfstate"]
  }

  statement {
    effect = "Allow"
    actions = [
      "dynamodb:GetItem",
      "dynamodb:PutItem",
      "dynamodb:DeleteItem",
    ]
    resources = ["arn:aws:dynamodb:${var.region}:${var.account_id}:table/terraform-state-lock-dynamo"]
  }

  statement {
    effect = "Allow"
    actions = [
      "iam:GetRole",
      "iam:GetRolePolicy",
      "iam:ListRolePolicies",
      "iam:ListAttachedRolePolicies",
    ]
    resources = ["arn:aws:iam::${var.account_id}:role/*"]
  }

  statement {
    effect = "Allow"
    actions = [
      "iam:GetPolicy",
      "iam:GetPolicyVersion",
      "iam:ListPolicyVersions",
    ]
    resources = ["arn:aws:iam::${var.account_id}:policy/*"]
  }

  statement {
    effect = "Allow"
    actions = [
      "secretsmanager:DescribeSecret",
      "secretsmanager:GetSecretValue",
      "secretsmanager:GetResourcePolicy",
    ]
    resources = ["arn:aws:secretsmanager:${var.region}:${var.account_id}:secret:*"]
  }

  statement {
    effect = "Allow"
    actions = [
      "logs:DescribeLogGroups",
      "logs:ListTagsLogGroup",
      "logs:ListTagsForResource",
    ]
    resources = [
      "arn:aws:logs:${var.region}:${var.account_id}:log-group:*",
      "arn:aws:logs:${var.region}:${var.account_id}:log-group::log-stream:",
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "events:DescribeRule",
      "events:ListTargetsByRule",
    ]
    resources = ["arn:aws:events:${var.region}:${var.account_id}:rule/*"]
  }

  statement {
    effect = "Allow"
    actions = [
      "ssm:GetParameter",
      "ssm:DescribeParameters",
    ]
    resources = ["arn:aws:ssm:${var.region}:${var.account_id}:parameter/*"]
  }
}
