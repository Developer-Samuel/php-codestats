# 🛠 Development Guide

This document outlines the local development workflow and CI/CD simulation for **php-codestats**. To maintain high architectural standards, we use localized runner simulations to ensure environment parity.

---

## 🏗 Local CI/CD Simulation

We use [nektos/act](https://github.com/nektos/act) to run GitHub Actions locally. This allows us to validate logic across multiple PHP versions (Matrix Strategy) without polluting the global git history.

### 1. Prerequisites (WSL2 / Linux)

> **Docker Engine** must be installed and running. If you are on Windows, ensure **Docker Desktop** is installed and [WSL2 integration](https://docs.docker.com/desktop/wsl/) is enabled for your distribution.

If you are developing on Windows via **WSL2**, install `act` directly into your Linux distribution:

```bash
# Download and install act
curl -s https://raw.githubusercontent.com/nektos/act/master/install.sh | sudo bash

# Move binary to global path
sudo mv bin/act /usr/local/bin/act

# Verify installation
act --version
```

> When running `act` for the first time, you will be prompted to choose a Docker image. Select **Medium**. It provides the optimal balance between speed and tool availability (required for PHP environment bootstrapping).

### 2. Automation via Makefile
To standardize execution and hide complex CLI flags, we use a `Makefile`. This ensures every contributor runs the same checks with the same parameters.

| Command           | Description                                |
|-------------------|--------------------------------------------|
| make ci           | Simulates a full GitHub push event         |
| make ci-codestats | Targets only the codestats job for testing |

## 📜 Makefile Reference

The project includes a Makefile to streamline local operations. Do not run act manually; use the following targets:

```bash
.PHONY: ci ci-codestats

# Runs all jobs
ci:
	act --container-architecture linux/amd64

# Runs a specific job 'codestats'
ci-codestats:
	act -j codestats --container-architecture linux/amd64
```

## ⚓ Environment Parity

We explicitly use `--container-architecture linux/amd64` to ensure that even on ARM-based machines (like Apple Silicon), the environment strictly mirrors the official GitHub-hosted runners.
