# How to structure a Ruby project that mixes business logic and API integrations?

I’m working on a Ruby project where the core functionality involves both **business logic** (data processing, validations, calculations) and **API integrations** (fetching and sending data to third-party services like Stripe, SendGrid, etc.).

At the moment, my project is structured as a simple Ruby app (not Rails), and I’ve been placing most of the logic into `lib/`. For example:

```ruby
# lib/payment_processor.rb
require 'stripe'

class PaymentProcessor
  def initialize(api_key)
    @client = Stripe::API.new(api_key)
  end

  def charge(amount, customer_id)
    # Some custom business rules here...
    @client.charge(customer: customer_id, amount: amount)
  end
end
```

But as the project grows, I’m running into questions about how to organize it properly:

* Should I separate **business logic** from **integration logic**? For example, having a `services/` folder for APIs and keeping domain logic in `lib/`?
* Are there common patterns in Ruby (outside of Rails) for handling this kind of architecture?
* How should I handle testing — should I mock API calls directly, or is there a cleaner pattern (e.g., using adapters)?

I’ve been reading about the [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/) and wondering if it applies well to Ruby projects without Rails.

**What are the best practices for structuring a Ruby project that mixes domain logic with multiple external API integrations?**

Would love to hear how other Ruby developers are approaching this, especially in non-Rails contexts.
