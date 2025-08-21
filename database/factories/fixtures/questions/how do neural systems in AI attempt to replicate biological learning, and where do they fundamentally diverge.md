# How do neural systems in AI attempt to replicate biological learning, and where do they fundamentally diverge?

I’ve been reading about **artificial neural networks (ANNs)** and how they are inspired by **biological neural systems** in the brain. Most introductory materials describe ANNs as “loosely modeled” after neurons, with nodes (neurons), connections (synapses), and weights (synaptic strength). However, when I look deeper into neuroscience, it seems like the resemblance is more metaphorical than literal.

Some specific points I’m trying to understand better:

* In **biological neural systems**, learning involves complex biochemical processes, neuroplasticity, spike timing, and neurotransmitters. In contrast, in **artificial neural networks**, learning is usually just weight adjustment via backpropagation and gradient descent.

    * Is backpropagation considered biologically plausible, or is it purely a mathematical optimization method without a biological counterpart?
    * Are there current AI research directions (like Hebbian learning or spiking neural networks) that try to bridge this gap more realistically?

* Biological brains are highly **energy efficient** and massively **parallel**, while ANNs (especially deep networks) require huge amounts of computation and energy on GPUs/TPUs.

    * Are there insights from neuroscience that are actively being applied to make ANNs more efficient?
    * How do neuromorphic chips (e.g., Intel’s Loihi) fit into this picture?

* The brain has a remarkable ability to perform **continual learning** without catastrophic forgetting, whereas ANNs struggle with this.

    * Are there well-established techniques in AI that draw directly from neural systems to address this problem?

* Finally, terminology can be confusing: when people talk about “neural systems” in AI, do they generally mean **standard deep learning architectures** (CNNs, RNNs, Transformers) or more biologically inspired systems like **spiking neural networks**?

I’ve gone through some sources like the [Stanford CS231n notes on neural networks](http://cs231n.stanford.edu/) and articles on **spiking neural networks** from the [Human Brain Project](https://www.humanbrainproject.eu/), but I’m still struggling to connect the dots between **AI neural systems** and **actual biological neural systems**.

**So my question is:**
To what extent are current neural systems in artificial intelligence genuinely inspired by biological learning, and what are the key areas where AI diverges sharply from neuroscience?

I’d love to hear perspectives from both the AI research side and anyone with neuroscience background who can highlight which similarities are meaningful and which are mostly metaphorical.
