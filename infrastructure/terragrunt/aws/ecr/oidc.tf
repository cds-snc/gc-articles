locals {
  ecr_tag_release = "gc-articles-ecr-tag-release"
  docker_push     = "gc-articles-docker-push"
  docker_pull     = "gc-articles-docker-pull"
}

module "ecr_tag_release" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v13.2.0"
  billing_tag_value = var.billing_tag_value
  roles = [
    {
      name      = local.ecr_tag_release
      repo_name = "gc-articles"
      claim     = "ref:refs/tags/v*"
    },
    {
      name      = local.docker_push
      repo_name = "gc-articles"
      claim     = "ref:refs/heads/main"
    },
    {
      name = local.docker_pull
      claims = [
        {
          repo_name = "gc-articles"
          claim     = "ref:refs/heads/main"
        },
        {
          repo_name = "gc-articles"
          claim     = "ref:refs/tags/v*"
        }
      ]
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

resource "aws_iam_role_policy_attachment" "docker_push" {
  role       = local.docker_push
  policy_arn = aws_iam_policy.docker_push.arn
  depends_on = [
    module.ecr_tag_release
  ]
}

resource "aws_iam_policy" "ecr_push" {
  name   = "wordpress-ecr-push"
  path   = "/"
  policy = data.aws_iam_policy_document.ecr_push.json
  tags   = var.core_tags
}

resource "aws_iam_policy" "docker_push" {
  name   = local.docker_push
  path   = "/"
  policy = data.aws_iam_policy_document.docker_push.json
  tags   = var.core_tags
}

data "aws_iam_policy_document" "ecr_push" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:BatchGetImage",
      "ecr:CompleteLayerUpload",
      "ecr:DescribeImages",
      "ecr:DescribeRepositories",
      "ecr:GetDownloadUrlForLayer",
      "ecr:GetRepositoryPolicy",
      "ecr:InitiateLayerUpload",
      "ecr:ListImages",
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

#trivy:ignore:AVD-AWS-0342
data "aws_iam_policy_document" "docker_push" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:BatchGetImage",
      "ecr:CompleteLayerUpload",
      "ecr:InitiateLayerUpload",
      "ecr:PutImage",
      "ecr:UploadLayerPart",
    ]
    resources = [
      aws_ecr_repository.wordpress.arn,
      aws_ecr_repository.apache.arn,
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

resource "aws_iam_role_policy_attachment" "docker_pull" {
  role       = local.docker_pull
  policy_arn = aws_iam_policy.docker_pull.arn

  depends_on = [module.ecr_tag_release]
}

resource "aws_iam_policy" "docker_pull" {
  name   = local.docker_pull
  path   = "/"
  policy = data.aws_iam_policy_document.docker_pull.json
  tags   = var.core_tags
}

data "aws_iam_policy_document" "docker_pull" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:BatchGetImage",
      "ecr:BatchImportUpstreamImage",
      "ecr:GetDownloadUrlForLayer"
    ]
    resources = [
      "arn:aws:ecr:${var.region}:${var.account_id}:repository/*"
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "ecr:GetAuthorizationToken",
    ]
    resources = ["*"]
  }
}