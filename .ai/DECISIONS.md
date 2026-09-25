# Decisions

## ADR-001 — Use repository-local AI project memory

- Date: 2026-09-25
- Status: Accepted
- Context: The project needs reusable task context and handoff information.
- Decision: Store project-specific workflow state in `.ai/` and keep reusable prompts in the separate `AI-Software-Factory` repository.
- Consequences: Future sessions can resume from `.ai/HANDOFF.md`; documentation must be kept current after meaningful work.
