.PHONY: ci ci-codestats

# Runs all jobs
ci:
	act --container-architecture linux/amd64

# Runs a specific job 'codestats'
ci-codestats:
	act -j codestats --container-architecture linux/amd64
