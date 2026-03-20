locals {
  ecr_tag_release = "gc-articles-ecr-tag-release"
  docker_deploy   = "gc-articles-docker-deploy"
}

module "ecr_tag_release" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v10.11.0"
  billing_tag_value = var.billing_tag_value
  roles = [
    {
      name      = local.ecr_tag_release
      repo_name = "cds-snc/gc-articles"
      claim     = "ref:refs/tags/v*"
    }
  ]
}

module "docker_deploy" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v10.11.0"
  billing_tag_value = var.billing_tag_value
  roles = [
    {
      name      = local.docker_deploy
      repo_name = "cds-snc/gc-articles"
      claim     = "ref:refs/heads/main"
    }
  ]
}

resource "aws_iam_role_policy_attachment" "ecr_tag_release" {
  role       = local.ecr_tag_release
  policy_arn = aws_iam_policy.ecr_push.arn
  depends_on = [
    module.ecr_tag_release
  ]
}

resource "aws_iam_role_policy_attachment" "docker_deploy_ecr" {
  role       = local.docker_deploy
  policy_arn = aws_iam_policy.ecr_push.arn
  depends_on = [
    module.docker_deploy
  ]
}

resource "aws_iam_role_policy_attachment" "docker_deploy_ecs" {
  role       = local.docker_deploy
  policy_arn = aws_iam_policy.ecs_deploy.arn
  depends_on = [
    module.docker_deploy
  ]
}

resource "aws_iam_policy" "ecr_push" {
  name   = "gc-articles-ecr-push"
  path   = "/"
  policy = data.aws_iam_policy_document.ecr_push.json
}

resource "aws_iam_policy" "ecs_deploy" {
  name   = "gc-articles-ecs-deploy"
  path   = "/"
  policy = data.aws_iam_policy_document.ecs_deploy.json
}

data "aws_iam_policy_document" "ecr_push" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:CompleteLayerUpload",
      "ecr:InitiateLayerUpload",
      "ecr:PutImage",
      "ecr:UploadLayerPart"
    ]
    resources = [
      aws_ecr_repository.wordpress.arn
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "ecr:GetAuthorizationToken"
    ]
    resources = ["*"]
  }
}

data "aws_iam_policy_document" "ecs_deploy" {
  statement {
    effect = "Allow"
    actions = [
      "ecs:RegisterTaskDefinition",
      "ecs:DescribeTaskDefinition"
    ]
    resources = ["*"]
  }

  statement {
    effect = "Allow"
    actions = [
      "ecs:UpdateService",
      "ecs:DescribeServices"
    ]
    resources = [
      "arn:aws:ecs:${var.region}:${var.account_id}:service/${var.ecs_cluster_name}/${var.ecs_service_name}"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "ecs:DescribeClusters"
    ]
    resources = [
      # "arn:aws:ecs:${var.region}:472286471787:${var.ecs_cluster_name}/${var.ecs_service_name}"
      "arn:aws:ecs:${var.region}:${var.account_id}:cluster/${var.ecs_cluster_name}"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "iam:PassRole"
    ]
    resources = [
      "arn:aws:iam::${var.account_id}:role/*-ecs-task"
    ]
    condition {
      test     = "StringEquals"
      variable = "iam:PassedToService"
      values   = ["ecs-tasks.amazonaws.com"]
    }
  }
}
