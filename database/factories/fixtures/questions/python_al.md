# Best practices for structuring a Python project that integrates AI/ML models into a production web app

I’m working on a product that uses **Python** for the backend and integrates some **AI/ML models** (currently PyTorch and scikit-learn). The app itself is a web application with a REST API (using **FastAPI**) that serves predictions to clients.

My current structure looks something like this:

```
/app
  /api
    routes.py
  /core
    config.py
  /ml
    model.pkl
    predict.py
main.py
```

The main challenges I’m facing are:

1. **Model loading**: Should the model be loaded globally at app startup (so it stays in memory), or should I load it fresh inside each request handler?
2. **Multiple models**: If the app uses multiple AI models (e.g., recommendation + classification), what’s a clean way to structure them? A `/ml/models/` directory with separate modules, or a single service class?
3. **Versioning models**: How do you typically handle different versions of the same model in production? Do you store them in versioned directories, use something like MLflow, or just keep them in S3 with version tags?
4. **Testing AI code**: Since AI models don’t always return deterministic results (due to randomness), what are best practices for writing tests around them?
5. **Deployment concerns**: I’ve seen some people recommend serving ML models as separate microservices (e.g., using TensorFlow Serving or TorchServe). Is that overkill for smaller products, or worth doing from the start?

I’ve checked the [FastAPI docs on dependencies](https://fastapi.tiangolo.com/tutorial/dependencies/) and [MLflow docs](https://mlflow.org/docs/latest/index.html), but I’d like to hear how other developers approach this in real-world projects.

For those of you who have shipped Python apps with AI features:

* How did you organize your project to keep the ML code clean and maintainable?
* Did you separate the AI part into its own service, or keep it inside the main app?

Would love to hear different approaches and war stories before I lock in a structure.
