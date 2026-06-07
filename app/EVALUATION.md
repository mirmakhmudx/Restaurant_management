# Technical Evaluation — BitePlate SRMS

**Unit 27: Advanced Programming — Task 3 Distinction Criterion**

---

## Were the Three Core Patterns the Best Fit?

### Command Pattern — KitchenQueue

The Command pattern was an excellent fit for the kitchen queue. Kitchen operations are fundamentally action-based — preparing an order is a discrete command with a defined receiver (the Chef) and a reversible outcome. The undo() requirement, present in the assignment specification, maps directly to Command's built-in undo capability without requiring additional state management infrastructure.

An alternative would have been a simple method call queue: `$chef->prepare($order)` stored as a closure array. This would work for basic execution but would not support undo without introducing ad-hoc state tracking — precisely the problem Command solves structurally. The Invoker/Receiver separation also mirrors the KitchenQueue/Chef domain boundary cleanly, making the pattern choice semantically natural rather than forced.

### Singleton Pattern — OrderHistoryService

The Singleton correctly addressed the requirement for a globally consistent audit log. However, the implementation reveals a genuine tension: the private constructor makes isolated unit testing difficult without the Laravel IoC container. In a pure production architecture, the Singleton guarantee would be achieved entirely through the container binding (`$this->app->singleton(...)`) without the static getInstance() method, preserving testability while maintaining single-instance behaviour.

The decision to persist entries to the `order_history_logs` database table — rather than relying solely on the in-memory `$sessionEntries` array — was architecturally correct. In-memory Singletons lose all state on server restart; a restaurant's audit log must survive process boundaries. This enhancement makes BitePlate's Singleton implementation more production-appropriate than the classical pattern description.

### Strategy Pattern — Pricing Engine

The Strategy pattern was the most precisely correct choice of the three. Four genuinely interchangeable pricing algorithms, runtime selection at checkout, and a strong probability of future additions (group discounts, seasonal promotions, corporate accounts) — this is the exact problem the Strategy pattern was designed to solve. The Open/Closed Principle benefit was demonstrated concretely during development: adding StaffPricing required one new class file and zero changes to the billing pipeline.

---

## Singleton Trade-offs: Testability and Thread Safety

The static getInstance() pattern introduces two non-trivial trade-offs. First, testability: a test cannot instantiate a fresh OrderHistoryService without the IoC container or a static reset method, making isolated unit tests harder to write. The mitigation in BitePlate is that AppServiceProvider binds the class via getInstance(), allowing tests to rebind to a mock without touching the class itself.

Second, thread safety: PHP's shared-nothing per-request architecture eliminates multi-threading concerns for standard web requests. However, queue workers processing multiple kitchen jobs sequentially within a single process accumulate entries in the `$sessionEntries` array across job boundaries. Production queue workers should clear the session cache between jobs. The database-backed log remains unaffected by this issue since each `OrderHistoryLog::create()` call is a discrete, atomic database write.

---

## Scalability Considerations: 50 Franchise Restaurants

At single-restaurant scale, BitePlate's architecture performs correctly. At 50-restaurant scale, several structural limitations emerge.

**Singleton breakdown:** Each web server process maintains its own Singleton instance. In a distributed deployment across multiple servers, the "global" guarantee breaks — different nodes hold different instances. The resolution is to migrate OrderHistoryService to a centralised microservice or adopt an event-sourcing architecture where domain events are published to a message broker (AWS SQS, Redis Streams) and consumed by a central log aggregator service.

**Strategy pattern:** Scales without architectural change. Strategies are stateless and instantiated per-request; adding franchise-specific pricing tiers requires only new strategy classes deployed to each branch's instance.

**Command pattern:** Benefits from durable queuing at scale. Replacing the in-memory KitchenQueue with Laravel Queue backed by Redis or AWS SQS provides delivery guarantees across network partitions, node failures, and deployment restarts — critical for a multi-restaurant operation where kitchen command loss has direct business impact.

**Composite pattern:** Scales transparently. The combo meal component tree is stored relationally in the database; additional franchise-specific combos are additional rows, not structural changes.

**Database:** PostgreSQL 16 handles single-restaurant load comfortably. At 50 restaurants, a read replica strategy and connection pooling (PgBouncer) would be required. The Repository pattern's database abstraction layer makes this migration transparent to the application code.

---

## Alternative Patterns Considered

| Implemented | Alternative Considered | Reason for Current Choice |
|-------------|----------------------|--------------------------|
| Singleton (OrderHistoryService) | Plain service bound by IoC | Singleton makes intent explicit for assessment; IoC-only is preferred in production |
| Strategy (Pricing) | Switch/match statement | Strategy scales to N pricing modes without code changes; switch grows linearly |
| Command (Kitchen) | Direct method calls | Command provides undo capability and Invoker/Receiver decoupling |
| Observer (Notifications) | Manual service calls in controller | Observer eliminates controller coupling; notifications are added without touching order logic |
| Composite (Combo meals) | Separate billing calculation | Composite allows uniform getPrice() calls regardless of item type |

