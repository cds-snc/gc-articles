locals {
  docker_deploy_role = "gc-articles-docker-deploy"
}


module "docker_deploy" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v10.11.4"
  billing_tag_value = var.billing_tag_value
  oidc_exists       = true

  roles = [
    {
      name      = local.docker_deploy_role
      repo_name = "gc-articles"
      claim     = "ref:refs/heads/main"
    }
  ]
}

resource "aws_iam_role_policy_attachment" "docker_deploy" {
  role       = local.docker_deploy_role
  policy_arn = aws_iam_policy.docker_deploy.arn
  depends_on = [module.docker_deploy]
}

resource "aws_iam_policy" "docker_deploy" {
  name   = local.docker_deploy_role
  path   = "/"
  policy = data.aws_iam_policy_document.docker_deploy.json
}

data "aws_iam_policy_document" "docker_deploy" {
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

  statement {
    sid    = "ECSService"
    effect = "Allow"
    actions = [
      "ecs:DescribeServices",
      "ecs:UpdateService",
    ]
    resources = [aws_ecs_service.wordpress_service.id]
  }

  statement {
    sid     = "IAMPassRole"
    effect  = "Allow"
    actions = ["iam:PassRole"]
    resources = [
      aws_iam_role.wordpress_ecs_task.arn
    ]
  }
}
